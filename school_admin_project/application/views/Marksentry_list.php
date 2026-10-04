<style>
.modal-overlay{position:fixed;inset:0;background:rgba(15,23,42,.5);display:none;align-items:center;justify-content:center;padding:16px;z-index:1000}
.modal-overlay.open{display:flex}
.modal{background:#fff;border-radius:12px;width:100%;max-width:1150px;height:92vh;display:flex;flex-direction:column;box-shadow:0 20px 50px rgba(0,0,0,.25)}
.modal-head{display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid #e2e8f0}
.modal-head h2{margin:0;font-size:18px}
.modal-close{background:none;border:0;font-size:26px;line-height:1;cursor:pointer;color:#64748b}
.modal form{display:flex;flex-direction:column;min-height:0;flex:1}
.modal-body{padding:20px;overflow:auto;flex:1}
.modal-foot{display:flex;justify-content:flex-end;align-items:center;gap:10px;padding:14px 20px;border-top:1px solid #e2e8f0;background:#f8fafc}
.modal-foot .field-error{margin:0 auto 0 0}
.top-grid{display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px}
.field-label{display:block;font-size:12px;font-weight:700;letter-spacing:.3px;text-transform:uppercase;margin:0 0 6px}
.req{color:#dc2626}
.field-input{width:100%;padding:11px 12px;border:1px solid #cbd5e1;border-radius:10px;font:inherit;font-size:14px;background:#f8fafc}
.field-input:focus{outline:2px solid #6366f1;outline-offset:1px;background:#fff}
.field-input:disabled{opacity:.7;cursor:not-allowed}
.field-error{color:#dc2626;font-size:13px}
.hint{padding:28px;text-align:center;color:#94a3b8;font-size:14px;border:1px dashed #cbd5e1;border-radius:10px;margin-top:20px}

/* marks sheet */
.sheet-wrap{margin-top:20px;border:1px solid #d6dbe4;border-radius:8px;overflow:auto;max-height:calc(92vh - 300px)}
.sheet{width:100%;border-collapse:collapse;min-width:640px}
.sheet th{position:sticky;top:0;z-index:2;background:#f1f3f6;color:#1e3a8a;font-size:13px;text-transform:uppercase;padding:10px 8px;border:1px solid #d6dbe4;text-align:center}
.sheet th small{display:block;font-size:11px;font-weight:600;text-transform:none;margin-top:2px}
.sheet td{border:1px solid #e2e8f0;padding:6px 8px;text-align:center;color:#1e3a8a;font-size:14px}
.sheet td.nm{text-align:left;text-transform:uppercase}
.sheet input{width:70px;padding:8px 6px;border:1px solid #cbd5e1;border-radius:8px;text-align:center;font:inherit;font-size:14px}
.sheet input:focus{outline:2px solid #6366f1;outline-offset:1px}
.sheet input.bad{border-color:#dc2626;background:#fef2f2}
.tag{display:inline-block;padding:3px 10px;border-radius:999px;background:#eef2ff;color:#4338ca;font-size:12px;font-weight:600}
[hidden]{display:none !important}
@media(max-width:700px){.top-grid{grid-template-columns:1fr}}
</style>

<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Mark List</h1>
      <p class="page-sub">Enter and manage exam marks for each class and division</p>
    </div>
    <button class="btn btn-primary" type="button" id="openAddModal">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
      Enter Marks
    </button>
  </div>

  <div class="card">
    <div class="table-toolbar">
      <div class="search-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="text" id="search" placeholder="Search exam, class or division…" aria-label="Search">
      </div>
    </div>
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th style="width:70px">SL</th>
            <th>Exam</th>
            <th style="width:120px">Class</th>
            <th>Division</th>
            <th style="width:80px;text-align:center">Edit</th>
            <th style="width:80px;text-align:center">Action</th>
          </tr>
        </thead>
        <tbody id="rows"></tbody>
      </table>
      <div class="empty" id="empty" hidden>No marks entered yet. Click "Enter Marks" to start.</div>
    </div>
    <div class="table-foot" id="count"></div>
  </div>
</main>

<div class="modal-overlay" id="addModal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="addTitle">
    <div class="modal-head">
      <h2 id="addTitle">Enter Marks</h2>
      <button class="modal-close" type="button" data-close aria-label="Close">&times;</button>
    </div>
    <form id="addForm" novalidate>
      <div class="modal-body">
        <div class="top-grid">
          <div><label class="field-label" for="mClass">Class <span class="req">*</span></label>
            <select id="mClass" class="field-input"></select></div>
          <div><label class="field-label" for="mDiv">Division <span class="req">*</span></label>
            <select id="mDiv" class="field-input"></select></div>
          <div><label class="field-label" for="mExam">Exam <span class="req">*</span></label>
            <select id="mExam" class="field-input"></select></div>
        </div>
        <div id="sheetArea"></div>
      </div>
      <div class="modal-foot">
        <p class="field-error" id="formError" hidden></p>
        <button type="button" class="btn" data-close>Cancel</button>
        <button type="submit" class="btn btn-primary" id="saveBtn">Save Marks</button>
      </div>
    </form>
  </div>
</div>

<script>
(function () {
  const $ = id => document.getElementById(id);
  const modal = $('addModal'), form = $('addForm'), errorEl = $('formError');

  /* ---- Master data (load from your backend later) ---- */
  const CLASSES = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];
  const DIVISIONS = { default: ['Pearl','Manikyam','Vydooryam','Marathakam','Indraneelam','Vajram'],
                      XI: ['Science','Commerce'], XII: ['Science','Commerce'] };
  const EXAMS = ['NB1','PA1','PA2','Half Yearly','Annual'];
  // subjects + max marks per class & exam (comes from the Mark Allocation page)
  const ALLOC = {
    'V|NB1': [{name:'English',max:5},{name:'Hindi',max:5},{name:'Malayalam',max:5},{name:'Mathematics',max:5},{name:'EVS',max:5}],
    'V|PA1': [{name:'English',max:50},{name:'Hindi',max:50},{name:'Malayalam',max:50},{name:'Mathematics',max:50},{name:'EVS',max:50}]
  };
  const STUDENTS = [
    {admNo:'ADM001',name:'Aadhi Anoop',cls:'V',div:'Pearl'},
    {admNo:'ADM002',name:'Aadhilakshmi M Nair',cls:'V',div:'Pearl'},
    {admNo:'ADM003',name:'Aadithlal T J',cls:'V',div:'Pearl'},
    {admNo:'ADM004',name:'Aarav Amith',cls:'V',div:'Pearl'},
    {admNo:'ADM005',name:'Aarav Jibin',cls:'V',div:'Pearl'},
    {admNo:'ADM006',name:'Adharv R',cls:'V',div:'Pearl'},
    {admNo:'ADM007',name:'Devika S',cls:'V',div:'Manikyam'}
  ];

  let items = [];
  let nextId = 1, editId = null;
  let sheet = null;   // {subjects, students} currently shown in modal

  const esc = s => String(s == null ? '' : s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  const divsFor = c => DIVISIONS[c] || DIVISIONS.default;
  const fill = (el, list, ph, val) => { el.innerHTML = `<option value="">${ph}</option>` +
    list.map(v => `<option value="${esc(v)}">${esc(v)}</option>`).join(''); el.value = val || ''; };

  fill($('mClass'), CLASSES, 'Select Class');
  fill($('mExam'), EXAMS, 'Select Exam');
  fill($('mDiv'), [], 'Select Class first'); $('mDiv').disabled = true;

  /* ---------- list ---------- */
  function render() {
    const q = $('search').value.trim().toLowerCase();
    const list = items.filter(i => !q || [i.exam, i.cls, i.div].some(v => v.toLowerCase().includes(q)));
    $('rows').innerHTML = list.map((i, n) => `
      <tr>
        <td class="num">${n + 1}</td>
        <td><strong>${esc(i.exam)}</strong></td>
        <td>${esc(i.cls)}</td>
        <td><span class="tag">${esc(i.div)}</span></td>
        <td style="text-align:center">
          <button class="icon-btn" title="Edit" data-edit="${i.id}" aria-label="Edit marks">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
          </button></td>
        <td style="text-align:center">
          <button class="icon-btn danger" title="Delete" data-del="${i.id}" aria-label="Delete marks">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14"/></svg>
          </button></td>
      </tr>`).join('');
    $('empty').hidden = list.length > 0;
    $('count').textContent = `Showing ${list.length} of ${items.length} mark lists`;
  }

  /* ---------- marks sheet ---------- */
  function buildSheet(saved) {
    const cls = $('mClass').value, div = $('mDiv').value, exam = $('mExam').value;
    const area = $('sheetArea');
    sheet = null;
    if (!cls || !div || !exam) {
      area.innerHTML = '<div class="hint">Select class, division and exam to enter marks.</div>';
      return;
    }
    const subjects = saved ? saved.subjects : (ALLOC[cls + '|' + exam] || []);
    if (!subjects.length) {
      area.innerHTML = '<div class="hint">No mark allocation found for this class and exam. Add it in Mark Allocation first.</div>';
      return;
    }
    const students = saved ? saved.rows.map(r => ({ admNo: r.admNo, name: r.name }))
                           : STUDENTS.filter(s => s.cls === cls && s.div === div);
    if (!students.length) {
      area.innerHTML = '<div class="hint">No students found in this class and division.</div>';
      return;
    }
    sheet = { subjects, students };
    area.innerHTML = `
      <div class="sheet-wrap"><table class="sheet">
        <thead><tr>
          <th style="width:60px">SL</th><th style="width:130px">Admission No</th><th style="text-align:left">Student Name</th>
          ${subjects.map(s => `<th>${esc(s.name)}<small>(Max: ${s.max})</small></th>`).join('')}
        </tr></thead>
        <tbody>${students.map((st, r) => `
          <tr>
            <td>${r + 1}</td><td>${esc(st.admNo || '-')}</td><td class="nm">${esc(st.name)}</td>
            ${subjects.map((s, c) => {
              const v = saved ? saved.rows[r].marks[s.name] : '';
              return `<td><input type="number" min="0" max="${s.max}" step="0.5" data-r="${r}" data-s="${c}" value="${v == null ? '' : esc(v)}" aria-label="${esc(st.name)} ${esc(s.name)}"></td>`;
            }).join('')}
          </tr>`).join('')}
        </tbody></table></div>`;
  }

  /* ---------- modal ---------- */
  const showError = msg => { errorEl.textContent = msg; errorEl.hidden = false; };

  function open(item) {
    form.reset();
    errorEl.hidden = true;
    editId = item ? item.id : null;
    $('addTitle').textContent = item ? 'Edit Marks' : 'Enter Marks';
    $('saveBtn').textContent = item ? 'Update Marks' : 'Save Marks';
    $('mClass').value = item ? item.cls : '';
    fill($('mDiv'), item ? divsFor(item.cls) : [], item ? 'Select Division' : 'Select Class first', item ? item.div : '');
    $('mDiv').disabled = !item;
    $('mExam').value = item ? item.exam : '';
    ['mClass','mDiv','mExam'].forEach(id => { if (item) $(id).disabled = true; });
    if (!item) $('mClass').disabled = $('mExam').disabled = false;
    buildSheet(item);
    modal.classList.add('open');
    modal.setAttribute('aria-hidden', 'false');
  }
  function close() {
    modal.classList.remove('open');
    modal.setAttribute('aria-hidden', 'true');
    editId = null;
  }

  $('mClass').addEventListener('change', () => {
    const c = $('mClass').value;
    fill($('mDiv'), c ? divsFor(c) : [], c ? 'Select Division' : 'Select Class first');
    $('mDiv').disabled = !c;
    buildSheet(null);
  });
  $('mDiv').addEventListener('change', () => buildSheet(null));
  $('mExam').addEventListener('change', () => buildSheet(null));

  $('openAddModal').addEventListener('click', () => open(null));
  modal.querySelectorAll('[data-close]').forEach(b => b.addEventListener('click', close));
  modal.addEventListener('click', e => { if (e.target === modal) close(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.classList.contains('open')) close(); });

  /* ---------- save ---------- */
  form.addEventListener('submit', e => {
    e.preventDefault();
    errorEl.hidden = true;
    const cls = $('mClass').value, div = $('mDiv').value, exam = $('mExam').value;
    if (!cls) return showError('Please select a class.');
    if (!div) return showError('Please select a division.');
    if (!exam) return showError('Please select an exam.');
    if (!sheet) return showError('There are no students or subjects to enter marks for.');
    if (items.some(i => i.id !== editId && i.cls === cls && i.div === div && i.exam === exam))
      return showError('Marks for this class, division and exam already exist. Edit them from the list.');

    const rows = sheet.students.map(s => ({ admNo: s.admNo, name: s.name, marks: {} }));
    let filled = 0, bad = false;
    form.querySelectorAll('.sheet input').forEach(inp => {
      inp.classList.remove('bad');
      const sub = sheet.subjects[+inp.dataset.s];
      if (inp.value === '') return;
      const v = Number(inp.value);
      if (isNaN(v) || v < 0 || v > sub.max) { inp.classList.add('bad'); bad = true; return; }
      rows[+inp.dataset.r].marks[sub.name] = v;
      filled++;
    });
    if (bad) return showError('Some marks are invalid. Marks must be between 0 and the maximum shown.');
    if (!filled) return showError('Please enter marks for at least one student.');

    const data = { cls, div, exam, subjects: sheet.subjects, rows };
    if (editId) {
      // TODO: fetch('/api/marks/' + editId, { method: 'PUT', headers: {'Content-Type':'application/json'}, body: JSON.stringify(data) })
      items = items.map(i => i.id === editId ? Object.assign({ id: editId }, data) : i);
    } else {
      // TODO: fetch('/api/marks', { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify(data) })
      items.push(Object.assign({ id: nextId++ }, data));
    }
    items.sort((a, b) => CLASSES.indexOf(a.cls) - CLASSES.indexOf(b.cls) || a.div.localeCompare(b.div) || EXAMS.indexOf(a.exam) - EXAMS.indexOf(b.exam));
    render();
    close();
  });

  $('rows').addEventListener('click', e => {
    const ed = e.target.closest('[data-edit]');
    const del = e.target.closest('[data-del]');
    if (ed) { const item = items.find(i => i.id === +ed.dataset.edit); if (item) open(item); }
    if (del && confirm('Delete this mark list?')) {
      // TODO: fetch('/api/marks/' + del.dataset.del, { method: 'DELETE' })
      items = items.filter(i => i.id !== +del.dataset.del);
      render();
    }
  });

  $('search').addEventListener('input', render);
  render();
})();
</script>