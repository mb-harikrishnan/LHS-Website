<style>
.modal-overlay{position:fixed;inset:0;background:rgba(15,23,42,.5);display:none;align-items:center;justify-content:center;padding:16px;z-index:1000}
.modal-overlay.open{display:flex}
.modal{background:#fff;border-radius:12px;width:100%;max-width:900px;max-height:92vh;display:flex;flex-direction:column;box-shadow:0 20px 50px rgba(0,0,0,.25)}
.modal-head{display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid #e2e8f0}
.modal-head h2{margin:0;font-size:18px}
.modal-close{background:none;border:0;font-size:26px;line-height:1;cursor:pointer;color:#64748b}
.modal form{display:flex;flex-direction:column;min-height:0;flex:1}
.modal-body{padding:8px 20px 20px;overflow-y:auto;flex:1}
.modal-foot{display:flex;justify-content:flex-end;gap:10px;padding:14px 20px;border-top:1px solid #e2e8f0;background:#f8fafc}
.sec-title{font-size:12px;font-weight:700;letter-spacing:.5px;text-transform:uppercase;color:#4f46e5;margin:18px 0 10px;padding-bottom:6px;border-bottom:1px solid #e2e8f0}
.grid{display:grid;grid-template-columns:1fr 1fr;gap:12px 16px}
.grid .full{grid-column:1/-1}
.field-label{display:block;font-size:11px;font-weight:700;text-transform:uppercase;margin:0 0 5px}
.req{color:#dc2626}
.field-input{width:100%;padding:9px 10px;border:1px solid #cbd5e1;border-radius:8px;font:inherit;font-size:14px;background:#f8fafc}
.field-input:focus{outline:2px solid #6366f1;outline-offset:1px;background:#fff}
.field-input:disabled{cursor:not-allowed;opacity:.7}
textarea.field-input{min-height:80px;resize:vertical}
.field-error{color:#dc2626;font-size:13px;margin:14px 0 0}
.d-item{padding:10px 12px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px}
.d-item small{display:block;font-size:11px;font-weight:700;text-transform:uppercase;color:#64748b;margin-bottom:3px}
.d-item span{font-size:14px;word-break:break-word}
.tag{display:inline-block;padding:3px 10px;border-radius:999px;background:#eef2ff;color:#4338ca;font-size:12px;font-weight:600}
[hidden]{display:none !important}
@media(max-width:640px){.grid{grid-template-columns:1fr}}
</style>





<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Students</h1>
      <p class="page-sub">Manage student admissions and details</p>
    </div>
    <button class="btn btn-primary" type="button" id="openAddModal">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
      Add Student
    </button>
  </div>

  <div class="card">
    <div class="table-toolbar">
      <div class="search-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="text" id="search" placeholder="Search by admission no, name, class…" aria-label="Search students">
      </div>
    </div>
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th style="width:70px">Sl No</th>
            <th>Admission No</th>
            <th>Name</th>
            <th>Class</th>
            <th>Division</th>
            <th style="width:150px;text-align:right">Actions</th>
          </tr>
        </thead>
        <tbody id="rows"></tbody>
      </table>
      <div class="empty" id="empty" hidden>No students found. Add one with the button above.</div>
    </div>
    <div class="table-foot" id="count"></div>
  </div>
</main>

<!-- Add / Edit modal -->
<div class="modal-overlay" id="formModal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="formTitle">
    <div class="modal-head">
      <h2 id="formTitle">Add Student</h2>
      <button class="modal-close" type="button" data-close aria-label="Close">&times;</button>
    </div>
    <form id="studentForm" novalidate>
      <div class="modal-body">

        <div class="sec-title">Basic Information</div>
        <div class="grid">
          <div><label class="field-label" for="admNo">Admission Number <span class="req">*</span></label>
            <input class="field-input" id="admNo" placeholder="Enter Admission Number"></div>
          <div><label class="field-label" for="aadhar">Aadhar Number <span class="req">*</span></label>
            <input class="field-input" id="aadhar" placeholder="Enter Aadhar Number" inputmode="numeric" maxlength="12"></div>
          <div><label class="field-label" for="name">Student Name <span class="req">*</span></label>
            <input class="field-input" id="name" placeholder="Enter Student Name"></div>
          <div><label class="field-label" for="gender">Gender</label>
            <select class="field-input" id="gender">
              <option value="">Select Gender</option><option>Male</option><option>Female</option><option>Other</option>
            </select></div>
          <div><label class="field-label" for="dob">Date of Birth</label>
            <input class="field-input" type="date" id="dob"></div>
          <div><label class="field-label" for="mobile">Mobile Number</label>
            <input class="field-input" id="mobile" placeholder="Enter Mobile Number" inputmode="numeric" maxlength="10"></div>
        </div>

        <div class="sec-title">Academic Information</div>
        <div class="grid">
          <div><label class="field-label" for="cls">Class <span class="req">*</span></label>
            <select class="field-input" id="cls"></select></div>
          <div><label class="field-label" for="div">Division <span class="req">*</span></label>
            <select class="field-input" id="div"></select></div>
        </div>

        <div class="sec-title">Personal Details</div>
        <div class="grid">
          <div><label class="field-label" for="religion">Religion</label>
            <input class="field-input" id="religion"></div>
          <div><label class="field-label" for="caste">Caste</label>
            <input class="field-input" id="caste"></div>
          <div><label class="field-label" for="tongue">Mother Tongue</label>
            <input class="field-input" id="tongue"></div>
        </div>

        <div class="sec-title">Address Details</div>
        <div class="grid">
          <div class="full"><label class="field-label" for="address">Address</label>
            <textarea class="field-input" id="address" placeholder="Enter Address"></textarea></div>
          <div><label class="field-label" for="country">Country</label>
            <select class="field-input" id="country"></select></div>
          <div><label class="field-label" for="state">State</label>
            <select class="field-input" id="state"></select></div>
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

<!-- Details modal -->
<div class="modal-overlay" id="detailModal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="detailTitle">
    <div class="modal-head">
      <h2 id="detailTitle">Student Details</h2>
      <button class="modal-close" type="button" data-close aria-label="Close">&times;</button>
    </div>
    <div class="modal-body" id="detailBody"></div>
    <div class="modal-foot"><button type="button" class="btn" data-close>Close</button></div>
  </div>
</div>




<script>
(function () {
  const $ = id => document.getElementById(id);
  const formModal = $('formModal'), detailModal = $('detailModal');
  const form = $('studentForm'), errorEl = $('formError');

  // Master data (load these from your backend later)
  const CLASSES = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];
  const DIVISIONS = {                      // class -> divisions (from your Class Division page)
    default: ['Pearl','Manikyam','Vydooryam','Marathakam','Indraneelam','Vajram'],
    XI: ['Science','Commerce'], XII: ['Science','Commerce']
  };
  const COUNTRIES = { India: ['Kerala','Tamil Nadu','Karnataka','Andhra Pradesh','Telangana','Maharashtra','Delhi'],
                      Other: ['Other'] };

  let items = [
    { id:1, admNo:'ADM001', aadhar:'123412341234', name:'Anjali Menon', gender:'Female', dob:'2015-06-12',
      mobile:'9876543210', cls:'V', div:'Pearl', religion:'Hindu', caste:'Nair', tongue:'Malayalam',
      address:'House 12, MG Road, Kochi', country:'India', state:'Kerala' }
  ];
  let nextId = 2, editId = null;

  const esc = s => String(s == null ? '' : s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  const fmtDate = d => d ? d.split('-').reverse().join('-') : '';
  const divsFor = c => DIVISIONS[c] || DIVISIONS.default;

  function fillSelect(el, list, placeholder, value) {
    el.innerHTML = '<option value="">' + placeholder + '</option>' +
      list.map(v => `<option value="${esc(v)}">${esc(v)}</option>`).join('');
    el.value = value || '';
  }
  function loadDivisions(value) {
    const c = $('cls').value;
    fillSelect($('div'), c ? divsFor(c) : [], c ? 'Select Division' : 'Select Class first', value);
    $('div').disabled = !c;
  }
  function loadStates(value) {
    const c = $('country').value;
    fillSelect($('state'), c ? COUNTRIES[c] : [], c ? 'Select State' : 'Select Country first', value);
    $('state').disabled = !c;
  }
  fillSelect($('cls'), CLASSES, 'Select Class');
  fillSelect($('country'), Object.keys(COUNTRIES), 'Select Country');
  $('cls').addEventListener('change', () => loadDivisions(''));
  $('country').addEventListener('change', () => loadStates(''));

  /* ---------- list ---------- */
  function render() {
    const q = $('search').value.trim().toLowerCase();
    const list = items.filter(i => !q ||
      [i.admNo, i.name, i.cls, i.div].some(v => v.toLowerCase().includes(q)));
    $('rows').innerHTML = list.map((i, n) => `
      <tr>
        <td class="num">${n + 1}</td>
        <td><strong>${esc(i.admNo)}</strong></td>
        <td>${esc(i.name)}</td>
        <td>${esc(i.cls)}</td>
        <td><span class="tag">${esc(i.div)}</span></td>
        <td><div class="row-actions" style="justify-content:flex-end;">
          <button class="icon-btn" title="Details" data-view="${i.id}" aria-label="Details of ${esc(i.name)}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12Z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
          <button class="icon-btn" title="Edit" data-edit="${i.id}" aria-label="Edit ${esc(i.name)}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
          </button>
          <button class="icon-btn danger" title="Delete" data-del="${i.id}" aria-label="Delete ${esc(i.name)}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14"/></svg>
          </button>
        </div></td>
      </tr>`).join('');
    $('empty').hidden = list.length > 0;
    $('count').textContent = `Showing ${list.length} of ${items.length} students`;
  }

  /* ---------- modals ---------- */
  const show = m => { m.classList.add('open'); m.setAttribute('aria-hidden', 'false'); };
  const hide = m => { m.classList.remove('open'); m.setAttribute('aria-hidden', 'true'); };

  function openForm(s) {
    form.reset();
    errorEl.hidden = true;
    editId = s ? s.id : null;
    $('formTitle').textContent = s ? 'Edit Student' : 'Add Student';
    $('saveBtn').textContent = s ? 'Update' : 'Save';
    $('admNo').value = s ? s.admNo : '';
    $('aadhar').value = s ? s.aadhar : '';
    $('name').value = s ? s.name : '';
    $('gender').value = s ? s.gender : '';
    $('dob').value = s ? s.dob : '';
    $('mobile').value = s ? s.mobile : '';
    $('cls').value = s ? s.cls : '';
    loadDivisions(s ? s.div : '');
    $('religion').value = s ? s.religion : '';
    $('caste').value = s ? s.caste : '';
    $('tongue').value = s ? s.tongue : '';
    $('address').value = s ? s.address : '';
    $('country').value = s ? s.country : '';
    loadStates(s ? s.state : '');
    show(formModal);
    $('admNo').focus();
  }

  function openDetails(s) {
    const rows = [
      ['Admission Number', s.admNo], ['Aadhar Number', s.aadhar],
      ['Student Name', s.name], ['Gender', s.gender],
      ['Date of Birth', fmtDate(s.dob)], ['Mobile Number', s.mobile],
      ['Class', s.cls], ['Division', s.div],
      ['Religion', s.religion], ['Caste', s.caste],
      ['Mother Tongue', s.tongue], ['Country', s.country],
      ['State', s.state]
    ];
    $('detailBody').innerHTML = '<div class="grid" style="margin-top:16px">' +
      rows.map(r => `<div class="d-item"><small>${r[0]}</small><span>${esc(r[1]) || '-'}</span></div>`).join('') +
      `<div class="d-item full"><small>Address</small><span>${esc(s.address) || '-'}</span></div></div>`;
    $('detailTitle').textContent = s.name + ' - Details';
    show(detailModal);
  }

  $('openAddModal').addEventListener('click', () => openForm(null));
  [formModal, detailModal].forEach(m => {
    m.querySelectorAll('[data-close]').forEach(b => b.addEventListener('click', () => hide(m)));
    m.addEventListener('click', e => { if (e.target === m) hide(m); });
  });
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { hide(formModal); hide(detailModal); }
  });

  /* ---------- save ---------- */
  const showError = msg => { errorEl.textContent = msg; errorEl.hidden = false; };

  form.addEventListener('submit', e => {
    e.preventDefault();
    const s = {
      id: editId, admNo: $('admNo').value.trim(), aadhar: $('aadhar').value.trim(),
      name: $('name').value.trim(), gender: $('gender').value, dob: $('dob').value,
      mobile: $('mobile').value.trim(), cls: $('cls').value, div: $('div').value,
      religion: $('religion').value.trim(), caste: $('caste').value.trim(),
      tongue: $('tongue').value.trim(), address: $('address').value.trim(),
      country: $('country').value, state: $('state').value
    };
    if (!s.admNo) return showError('Please enter the admission number.');
    if (items.some(i => i.id !== editId && i.admNo.toLowerCase() === s.admNo.toLowerCase()))
      return showError('This admission number already exists.');
    if (!/^\d{12}$/.test(s.aadhar)) return showError('Aadhar number must be 12 digits.');
    if (!s.name) return showError('Please enter the student name.');
    if (s.mobile && !/^\d{10}$/.test(s.mobile)) return showError('Mobile number must be 10 digits.');
    if (!s.cls) return showError('Please select a class.');
    if (!s.div) return showError('Please select a division.');

    if (editId) {
      // TODO: fetch('/api/students/' + editId, { method: 'PUT', headers: {'Content-Type':'application/json'}, body: JSON.stringify(s) })
      items = items.map(i => i.id === editId ? s : i);
    } else {
      // TODO: fetch('/api/students', { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify(s) })
      s.id = nextId++;
      items.unshift(s);
    }
    render();
    hide(formModal);
  });

  /* ---------- row buttons ---------- */
  $('rows').addEventListener('click', e => {
    const v = e.target.closest('[data-view]'), ed = e.target.closest('[data-edit]'), del = e.target.closest('[data-del]');
    const find = id => items.find(i => i.id === +id);
    if (v) openDetails(find(v.dataset.view));
    if (ed) openForm(find(ed.dataset.edit));
    if (del && confirm('Delete this student?')) {
      // TODO: fetch('/api/students/' + del.dataset.del, { method: 'DELETE' })
      items = items.filter(i => i.id !== +del.dataset.del);
      render();
    }
  });

  $('search').addEventListener('input', render);
  render();
})();
</script>