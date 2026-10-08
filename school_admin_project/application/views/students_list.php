<?php
/* Save as: application/views/members_area/students_list.php
   Receives from controller:
     $details  rows of students (with className, divName)     $links     pagination html
     $filters  name / class / division                        $total, $start, $per_page
     $res      class_master rows (cmId, cmName)               $res1      division_master rows (dmId, dmName)
     $div_map  class id => [{id, name}]                       $genders   value => label
     $perm     can_view / can_add / can_edit / can_delete     $is_admin, $assigned */

$can_add  = !empty($perm['can_add']);
$can_edit = !empty($perm['can_edit']);
$can_del  = !empty($perm['can_delete']);

$classes_js = array();
foreach ($res as $c) { $classes_js[] = array('id' => (string)$c->cmId, 'name' => $c->cmName); }
$divs_js = array();
foreach ($res1 as $d) { $divs_js[] = array('id' => (string)$d->dmId, 'name' => $d->dmName); }

$from = ($total > 0) ? $start + 1 : 0;
$to   = min($start + $per_page, $total);
$has_filter = ($filters['name'] !== '') || ($is_admin && ($filters['class'] !== '' || $filters['division'] !== ''));
?>
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

/* filter bar */
.filter-bar{display:flex;align-items:center;gap:10px;flex-wrap:wrap;width:100%}
.filter-bar .search-box{flex:1;min-width:220px}
.filter-bar select:disabled{background:#f1f5f9;cursor:not-allowed;opacity:.8}
.lock-note{display:inline-flex;align-items:center;gap:6px;font-size:12px;color:#64748b}

/* action buttons */
.act-btn{display:inline-flex;align-items:center;justify-content:center;gap:6px;height:34px;padding:0 12px;border-radius:8px;font:inherit;font-size:13px;font-weight:600;line-height:1;cursor:pointer;text-decoration:none;white-space:nowrap;border:1px solid transparent;transition:background .15s,color .15s,border-color .15s,box-shadow .15s}
.act-btn svg{width:15px;height:15px;flex:none}
.act-btn:focus-visible{outline:2px solid #93c5fd;outline-offset:2px}
.act-btn.dl{background:#eff6ff;color:#1d4ed8;border-color:#bfdbfe}
.act-btn.dl:hover{background:#dbeafe;border-color:#93c5fd}
.act-btn.ed{background:#f8fafc;color:#334155;border-color:#cbd5e1}
.act-btn.ed:hover{background:#e2e8f0}
.act-btn.rm{background:#fef2f2;color:#b91c1c;border-color:#fecaca}
.act-btn.rm:hover{background:#dc2626;color:#fff;border-color:#dc2626;box-shadow:0 4px 10px rgba(220,38,38,.25)}

/* pagination */
.table-foot{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap}
.pager{display:flex;gap:6px;list-style:none;margin:0;padding:0;flex-wrap:wrap}
.pager a,.pager span{display:block;min-width:34px;padding:7px 10px;border:1px solid #cbd5e1;border-radius:8px;text-align:center;font-size:13px;text-decoration:none;color:inherit;background:#fff}
.pager a:hover{background:#eef2ff;border-color:#6366f1}
.pager .active span{background:#4f46e5;color:#fff;border-color:#4f46e5}
.notice{padding:28px;text-align:center;color:#64748b}
</style>

<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Students</h1>
      <p class="page-sub">Manage student admissions and details</p>
    </div>
    <?php if ($can_add && ($is_admin || $assigned)): ?>
    <button class="btn btn-primary" type="button" id="openAddModal">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
      Add Student
    </button>
    <?php endif; ?>
  </div>

  <div class="card">
    <?php if (!$is_admin && !$assigned): ?>
      <div class="notice">
        No class and division is assigned to your account, so there are no students to show.
        Please contact the administrator.
      </div>
    <?php else: ?>

    <div class="table-toolbar">
      <form class="filter-bar" method="get" action="<?= site_url('students_list') ?>">
        <div class="search-box">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
          <input type="text" name="name" value="<?= html_escape($filters['name']) ?>" placeholder="Search by name or admission no…" aria-label="Search students">
        </div>

        <select class="filter-select" name="class" aria-label="Class" <?= $is_admin ? '' : 'disabled' ?>>
          <option value="">All Classes</option>
          <?php foreach ($res as $c): ?>
            <option value="<?= html_escape($c->cmId) ?>" <?= (string)$filters['class'] === (string)$c->cmId ? 'selected' : '' ?>><?= html_escape($c->cmName) ?></option>
          <?php endforeach; ?>
        </select>

        <select class="filter-select" name="division" aria-label="Division" <?= $is_admin ? '' : 'disabled' ?>>
          <option value="">All Divisions</option>
          <?php foreach ($res1 as $d): ?>
            <option value="<?= html_escape($d->dmId) ?>" <?= (string)$filters['division'] === (string)$d->dmId ? 'selected' : '' ?>><?= html_escape($d->dmName) ?></option>
          <?php endforeach; ?>
        </select>

        <button type="submit" class="btn btn-primary">Filter</button>
        <?php if ($has_filter): ?>
          <a class="btn" href="<?= site_url('students_list') ?>">Clear</a>
        <?php endif; ?>
        <?php if (!$is_admin): ?>
          <span class="lock-note">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
            Class and division are fixed for your account
          </span>
        <?php endif; ?>
      </form>
    </div>

    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th>Sl No</th>
            <th>Admission Number</th>
            <th>Name</th>
            <th>Class</th>
            <th>Divition</th>
            <th>Details</th>
            <th>Edit</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="rows">
          <?php foreach ($details as $i =>  $s):
            $dob = ($s->smDOB && $s->smDOB !== '0000-00-00') ? date('Y-m-d', strtotime($s->smDOB)) : '';
            $row = array(
                'id'          => (int)$s->smId,
                'adm_no'      => $s->smAdmissionNo,
                'aadhar'      => $s->smAadharNo,
                'name'        => $s->smName,
                'gender'      => (string)$s->smGender,
                'dob'         => $dob,
                'mobile'      => $s->smMobile,
                'class_id'    => (string)$s->smClass,
                'division_id' => (string)$s->smDiv,
                'class_name'  => $s->className,
                'division_name' => $s->divName,
                'religion'    => $s->smReligion,
                'caste'       => $s->smCaste,
                'tongue'      => $s->smMotherTongue,
                'address'     => $s->smAddress,
                'country'     => $s->smCountry,
                'state'       => $s->smState
            );
          ?>
          <tr data-s="<?= html_escape(json_encode($row)) ?>">
              <td><?= $start + $i + 1 ?></td>
            <td><strong><?= html_escape($s->smAdmissionNo) ?></strong></td>
            <td><?= html_escape($s->smName) ?></td>
            <td><?= html_escape($s->className) ?></td>
            <td><span class="tag"><?= html_escape($s->divName) ?></span></td>
            <td>
              <button class="act-btn dl" type="button" data-view aria-label="Details of <?= html_escape($s->smName) ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                Details
              </button>
            </td>
            <td>
              <?php if ($can_edit): ?>
              <button class="act-btn ed" type="button" data-edit aria-label="Edit <?= html_escape($s->smName) ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                Edit
              </button>
              <?php else: ?>&mdash;<?php endif; ?>
            </td>
            <td>
              <?php if ($can_del): ?>
              <button class="act-btn rm" type="button" data-del aria-label="Delete <?= html_escape($s->smName) ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14M10 11v6M14 11v6"/></svg>
                Delete
              </button>
              <?php else: ?>&mdash;<?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php if (empty($details)): ?>
        <div class="empty">No students found<?= $can_add ? '. Add one with the button above.' : '.' ?></div>
      <?php endif; ?>
    </div>

    <div class="table-foot">
      <span><?= $total > 0 ? "Showing {$from}&ndash;{$to} of {$total} students" : 'Showing 0 students' ?></span>
      <?= $links ?>
    </div>
    <?php endif; ?>
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
      <input type="hidden" id="sId" value="0">
      <div class="modal-body">

        <div class="sec-title">Basic Information</div>
        <div class="grid">
          <div><label class="field-label" for="admNo">Admission Number <span class="req">*</span></label>
            <input class="field-input" id="admNo" placeholder="Enter Admission Number" maxlength="30"></div>
          <div><label class="field-label" for="aadhar">Aadhar Number <span class="req">*</span></label>
            <input class="field-input" id="aadhar" placeholder="Enter Aadhar Number" inputmode="numeric" maxlength="12"></div>
          <div><label class="field-label" for="name">Student Name <span class="req">*</span></label>
            <input class="field-input" id="name" placeholder="Enter Student Name" maxlength="100"></div>
          <div><label class="field-label" for="gender">Gender <span class="req">*</span></label>
            <select class="field-input" id="gender"></select></div>
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
            <input class="field-input" id="religion" maxlength="50"></div>
          <div><label class="field-label" for="caste">Caste</label>
            <input class="field-input" id="caste" maxlength="50"></div>
          <div><label class="field-label" for="tongue">Mother Tongue</label>
            <input class="field-input" id="tongue" maxlength="50"></div>
        </div>

        <div class="sec-title">Address Details</div>
        <div class="grid">
          <div class="full"><label class="field-label" for="address">Address</label>
            <textarea class="field-input" id="address" placeholder="Enter Address" maxlength="500"></textarea></div>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function () {
  const $ = id => document.getElementById(id);
  const formModal = $('formModal'), detailModal = $('detailModal');
  const form = $('studentForm'), errorEl = $('formError'), saveBtn = $('saveBtn');

  const SAVE_URL   = '<?= site_url('save_student') ?>';
  const DELETE_URL = '<?= site_url('delete_student') ?>';
  const CSRF_NAME  = '<?= $this->security->get_csrf_token_name() ?>';
  let   CSRF_HASH  = '<?= $this->security->get_csrf_hash() ?>';

  const IS_ADMIN    = <?= $is_admin ? 'true' : 'false' ?>;
  const SCOPE_CLASS = <?= json_encode((string)$filters['class']) ?>;      // locked class id (non-admin)
  const SCOPE_DIV   = <?= json_encode((string)$filters['division']) ?>;   // locked division id (non-admin)

  const CLASSES   = <?= json_encode($classes_js) ?>;   // [{id, name}]
  const DIVS_ALL  = <?= json_encode($divs_js) ?>;      // [{id, name}]
  const DIV_MAP   = <?= json_encode($div_map) ?>;      // classId -> [{id, name}]
  const GENDERS   = <?= json_encode($genders) ?>;      // value -> label
  const COUNTRIES = { India: ['Kerala','Tamil Nadu','Karnataka','Andhra Pradesh','Telangana','Maharashtra','Delhi'],
                      Other: ['Other'] };

  const esc = s => String(s == null ? '' : s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  const fmtDate = d => d ? d.split('-').reverse().join('-') : '';
  const showError = m => { errorEl.textContent = m; errorEl.hidden = false; };
  const show = m => { m.classList.add('open'); m.setAttribute('aria-hidden', 'false'); };
  const hide = m => { m.classList.remove('open'); m.setAttribute('aria-hidden', 'true'); };

  /* ---------- selects ---------- */
  // items: [{id, name}]; value is kept even if it is not in the list
  function fillSelect(el, items, placeholder, value) {
    let list = items.slice();
    if (value && !list.some(i => String(i.id) === String(value))) list.push({ id: value, name: value });
    el.innerHTML = '<option value="">' + esc(placeholder) + '</option>' +
      list.map(i => `<option value="${esc(i.id)}">${esc(i.name)}</option>`).join('');
    el.value = value || '';
  }
  const strItems = arr => arr.map(v => ({ id: v, name: v }));
  const divsFor = c => (DIV_MAP[c] && DIV_MAP[c].length) ? DIV_MAP[c] : DIVS_ALL;

  function loadDivisions(value) {
    const c = $('cls').value;
    fillSelect($('div'), c ? divsFor(c) : [], c ? 'Select Division' : 'Select Class first', value);
    $('div').disabled = !IS_ADMIN || !c;
  }
  function loadStates(value) {
    const c = $('country').value;
    fillSelect($('state'), c && COUNTRIES[c] ? strItems(COUNTRIES[c]) : [], c ? 'Select State' : 'Select Country first', value);
    $('state').disabled = !c;
  }
  fillSelect($('gender'), Object.keys(GENDERS).map(k => ({ id: k, name: GENDERS[k] })), 'Select Gender');
  $('cls').addEventListener('change', () => loadDivisions(''));
  $('country').addEventListener('change', () => loadStates(''));

  /* ---------- add / edit form ---------- */
  function openForm(s) {
    form.reset(); errorEl.hidden = true;
    $('sId').value = s ? s.id : 0;
    $('formTitle').textContent = s ? 'Edit Student' : 'Add Student';
    saveBtn.textContent = s ? 'Update' : 'Save';

    $('admNo').value = s ? s.adm_no : '';
    $('aadhar').value = s ? s.aadhar : '';
    $('name').value = s ? s.name : '';
    $('gender').value = s ? s.gender : '';
    if (s && s.gender && !GENDERS.hasOwnProperty(s.gender)) fillSelect($('gender'), Object.keys(GENDERS).map(k => ({ id: k, name: GENDERS[k] })), 'Select Gender', s.gender);
    $('dob').value = s ? s.dob : '';
    $('mobile').value = s ? s.mobile : '';

    // class + division: free for admin, fixed for everyone else
    const cls = IS_ADMIN ? (s ? s.class_id : '') : SCOPE_CLASS;
    const div = IS_ADMIN ? (s ? s.division_id : '') : SCOPE_DIV;
    fillSelect($('cls'), CLASSES, 'Select Class', cls);
    $('cls').disabled = !IS_ADMIN;
    loadDivisions(div);

    $('religion').value = s ? s.religion || '' : '';
    $('caste').value = s ? s.caste || '' : '';
    $('tongue').value = s ? s.tongue || '' : '';
    $('address').value = s ? s.address || '' : '';
    fillSelect($('country'), strItems(Object.keys(COUNTRIES)), 'Select Country', s ? s.country : '');
    loadStates(s ? s.state : '');

    show(formModal); $('admNo').focus();
  }

  /* ---------- details modal ---------- */
  function openDetails(s) {
    const g = GENDERS.hasOwnProperty(s.gender) ? GENDERS[s.gender] : s.gender;
    const rows = [
      ['Admission Number', s.adm_no], ['Aadhar Number', s.aadhar],
      ['Student Name', s.name], ['Gender', g],
      ['Date of Birth', fmtDate(s.dob)], ['Mobile Number', s.mobile],
      ['Class', s.class_name], ['Division', s.division_name],
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

  const addBtn = $('openAddModal');
  if (addBtn) addBtn.addEventListener('click', () => openForm(null));
  [formModal, detailModal].forEach(m => {
    m.querySelectorAll('[data-close]').forEach(b => b.addEventListener('click', () => hide(m)));
    m.addEventListener('click', e => { if (e.target === m) hide(m); });
  });
  document.addEventListener('keydown', e => { if (e.key === 'Escape') { hide(formModal); hide(detailModal); } });

  /* ---------- save (insert / update) ---------- */
  form.addEventListener('submit', async e => {
    e.preventDefault();
    const v = id => $(id).value.trim();
    const id = +$('sId').value;
    const adm = v('admNo'), aadhar = v('aadhar'), name = v('name'), mobile = v('mobile');
    const cls = $('cls').value, div = $('div').value;

    if (!adm) return showError('Please enter the admission number.');
    if (!/^\d{12}$/.test(aadhar)) return showError('Aadhar number must be 12 digits.');
    if (!name) return showError('Please enter the student name.');
    if (!$('gender').value) return showError('Please select the gender.');
    if ($('dob').value && $('dob').value > new Date().toISOString().slice(0, 10)) return showError('Date of birth cannot be in the future.');
    if (mobile && !/^\d{10}$/.test(mobile)) return showError('Mobile number must be 10 digits.');
    if (!cls) return showError('Please select a class.');
    if (!div) return showError('Please select a division.');

    const fd = new FormData();
    fd.append('id', id); fd.append('adm_no', adm); fd.append('aadhar', aadhar); fd.append('name', name);
    fd.append('gender', $('gender').value); fd.append('dob', $('dob').value); fd.append('mobile', mobile);
    fd.append('class_id', cls); fd.append('division_id', div);
    fd.append('religion', v('religion')); fd.append('caste', v('caste')); fd.append('tongue', v('tongue'));
    fd.append('address', v('address')); fd.append('country', $('country').value); fd.append('state', $('state').value);
    fd.append(CSRF_NAME, CSRF_HASH);

    saveBtn.disabled = true;
    try {
      const res = await (await fetch(SAVE_URL, { method: 'POST', body: fd })).json();
      if (res.csrf) CSRF_HASH = res.csrf;
      if (!res.status) return showError(res.msg);
      hide(formModal);
      Swal.fire({ icon: 'success', title: 'Success', text: res.msg, timer: 1500, showConfirmButton: false })
          .then(() => location.reload());
    } catch (err) {
      showError('Something went wrong. Please try again.');
    } finally { saveBtn.disabled = false; }
  });

  /* ---------- row buttons ---------- */
  const rowsEl = $('rows');
  if (rowsEl) rowsEl.addEventListener('click', e => {
    const tr = e.target.closest('tr');
    if (!tr || !tr.dataset.s) return;
    const s = JSON.parse(tr.dataset.s);

    if (e.target.closest('[data-view]')) return openDetails(s);
    if (e.target.closest('[data-edit]')) return openForm(s);

    if (e.target.closest('[data-del]')) {
      Swal.fire({
        title: 'Are you sure?', text: s.name + ' will be deleted.', icon: 'warning',
        showCancelButton: true, confirmButtonColor: '#dc2626',
        confirmButtonText: 'Yes, delete it', cancelButtonText: 'Cancel'
      }).then(async r => {
        if (!r.isConfirmed) return;
        const fd = new FormData();
        fd.append('id', s.id); fd.append(CSRF_NAME, CSRF_HASH);
        try {
          const res = await (await fetch(DELETE_URL, { method: 'POST', body: fd })).json();
          if (res.csrf) CSRF_HASH = res.csrf;
          if (!res.status) return Swal.fire({ icon: 'error', title: 'Error', text: res.msg });
          Swal.fire({ icon: 'success', title: 'Deleted!', text: res.msg, timer: 1500, showConfirmButton: false })
              .then(() => location.reload());
        } catch (err) {
          Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong.' });
        }
      });
    }
  });
})();
</script>