<?php
/* Receives: $details, $perm, $is_admin, $assigned, $scope_class, $scope_div,
             $classes (cmId, cmName), $divisions (dmId, dmName), $div_map, $exams (emId, emDisplayName), $grades */
$can_add  = !empty($perm['can_add']);
$can_edit = !empty($perm['can_edit']);
$can_del  = !empty($perm['can_delete']);

$classes_js = array(); foreach ($classes as $c)   { $classes_js[] = array('id' => (string)$c->cmId, 'name' => $c->cmName); }
$divs_js    = array(); foreach ($divisions as $d) { $divs_js[]    = array('id' => (string)$d->dmId, 'name' => $d->dmName); }
$exams_js   = array(); foreach ($exams as $e)     { $exams_js[]   = array('id' => (string)$e->emId, 'name' => $e->emDisplayName); }
?>
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
.keys{margin:14px 0 0;font-size:12px;color:#64748b}
.keys kbd{display:inline-block;padding:1px 6px;border:1px solid #cbd5e1;border-bottom-width:2px;border-radius:5px;background:#f8fafc;font:inherit;font-size:12px}

/* marks sheet */
.sheet-wrap{margin-top:10px;border:1px solid #d6dbe4;border-radius:8px;overflow:auto;max-height:calc(92vh - 320px)}
.sheet{width:100%;border-collapse:collapse;min-width:640px}
.sheet th{position:sticky;top:0;z-index:2;background:#f1f3f6;color:#1e3a8a;font-size:13px;text-transform:uppercase;padding:10px 8px;border:1px solid #d6dbe4;text-align:center}
.sheet th small{display:block;font-size:11px;font-weight:600;text-transform:none;margin-top:2px}
.sheet td{border:1px solid #e2e8f0;padding:6px 8px;text-align:center;color:#1e3a8a;font-size:14px}
.sheet td.nm{text-align:left;text-transform:uppercase}
.sheet input{width:70px;padding:8px 6px;border:1px solid #cbd5e1;border-radius:8px;text-align:center;font:inherit;font-size:14px}
.sheet input:focus{outline:2px solid #6366f1;outline-offset:1px}
.sheet input.bad{border-color:#dc2626;background:#fef2f2}
.sheet input[readonly]{background:#f8fafc;color:#334155}
.tag{display:inline-block;padding:3px 10px;border-radius:999px;background:#eef2ff;color:#4338ca;font-size:12px;font-weight:600}
.tag.grade{background:#fef3c7;color:#92400e;margin-left:6px}
.tag.done{background:#dcfce7;color:#166534}
.notice{padding:28px;text-align:center;color:#64748b}

/* list buttons */
.btn-final{display:inline-flex;align-items:center;gap:6px;height:34px;padding:0 12px;border-radius:8px;border:1px solid #bbf7d0;background:#f0fdf4;color:#15803d;font:inherit;font-size:13px;font-weight:600;cursor:pointer;white-space:nowrap}
.btn-final:hover{background:#dcfce7}
.btn-final:disabled{background:#f1f5f9;border-color:#e2e8f0;color:#94a3b8;cursor:not-allowed}
.icon-btn:disabled{opacity:.4;cursor:not-allowed}
[hidden]{display:none !important}
@media(max-width:700px){.top-grid{grid-template-columns:1fr}}
</style>

<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Mark List</h1>
      <p class="page-sub">Enter and manage exam marks for each class and division</p>
    </div>
    <?php if ($can_add && ($is_admin || $assigned)): ?>
    <button class="btn btn-primary" type="button" id="openAddModal">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
      Enter Marks
    </button>
    <?php endif; ?>
  </div>

  <div class="card">
    <?php if (!$is_admin && !$assigned): ?>
      <div class="notice">
        No class and division is assigned to your account, so there are no mark lists to show.
        Please contact the administrator.
      </div>
    <?php else: ?>
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
            <th style="width:80px;text-align:center">View</th>
            <th style="width:80px;text-align:center">Edit</th>
            <th style="width:140px;text-align:center">Final Submit</th>
            <th style="width:80px;text-align:center">Action</th>
          </tr>
        </thead>
        <tbody id="rows">
          <?php foreach ($details as $i => $d):
            $m = array(
                'exam_id'     => (string)$d->mkEmId,
                'class_id'    => (string)$d->mkCmId,
                'division_id' => (string)$d->mkDmId,
                'exam_name'   => $d->emDisplayName,
                'class_name'  => $d->cmName,
                'div_name'    => $d->dmName,
                'complete'    => $d->complete ? 1 : 0,
                'final'       => $d->final ? 1 : 0,
                'filled'      => (int)$d->filled,
                'expected'    => (int)$d->expected
            );
          ?>
          <tr data-m="<?= html_escape(json_encode($m)) ?>">
            <td class="num"><?= $i + 1 ?></td>
            <td><strong><?= html_escape($d->emDisplayName) ?></strong><?php if ((int)$d->emIsGrade === 1): ?><span class="tag grade">Grade</span><?php endif; ?></td>
            <td><?= html_escape($d->cmName) ?></td>
            <td><span class="tag"><?= html_escape($d->dmName) ?></span></td>

            <td style="text-align:center">
              <button class="icon-btn" type="button" title="View" data-view aria-label="View marks">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12Z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </td>

            <td style="text-align:center">
              <?php if ($can_edit): ?>
              <button class="icon-btn" type="button" title="Edit" data-edit aria-label="Edit marks" <?= $d->final ? 'disabled' : '' ?>>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
              </button>
              <?php else: ?>&mdash;<?php endif; ?>
            </td>

            <td style="text-align:center">
              <?php if ($can_edit): ?>
                <button class="btn-final" type="button" data-final <?= $d->final ? 'disabled' : '' ?>><?= $d->final ? 'Submitted' : 'Final Submit' ?></button>
              <?php else: ?>
                <?= $d->final ? '<span class="tag done">Submitted</span>' : '&mdash;' ?>
              <?php endif; ?>
            </td>

            <td style="text-align:center">
              <?php if ($can_del): ?>
              <button class="icon-btn danger" type="button" title="Delete" data-del aria-label="Delete marks" <?= $d->final ? 'disabled' : '' ?>>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14"/></svg>
              </button>
              <?php else: ?>&mdash;<?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <div class="empty" id="empty" <?= empty($details) ? '' : 'hidden' ?>>No marks entered yet<?= $can_add ? '. Click "Enter Marks" to start.' : '.' ?></div>
    </div>
    <div class="table-foot" id="count"></div>
    <?php endif; ?>
  </div>
</main>

<!-- Enter / Edit / View marks modal -->
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
        <button type="button" class="btn" data-close>Close</button>
        <button type="submit" class="btn btn-primary" id="saveBtn">Save Marks</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function () {
  const $ = id => document.getElementById(id);
  const modal = $('addModal'), form = $('addForm'), errorEl = $('formError'), saveBtn = $('saveBtn'), area = $('sheetArea');

  const SHEET_URL  = '<?= site_url('marks_sheet') ?>';
  const SAVE_URL   = '<?= site_url('save_marks') ?>';
  const DELETE_URL = '<?= site_url('delete_marks') ?>';
  const FINAL_URL  = '<?= site_url('final_submit') ?>';
  const CSRF_NAME  = '<?= $this->security->get_csrf_token_name() ?>';
  let   CSRF_HASH  = '<?= $this->security->get_csrf_hash() ?>';

  const IS_ADMIN    = <?= $is_admin ? 'true' : 'false' ?>;
  const SCOPE_CLASS = <?= json_encode((string)$scope_class) ?>;
  const SCOPE_DIV   = <?= json_encode((string)$scope_div) ?>;

  const CLASSES  = <?= json_encode($classes_js) ?>;
  const DIVS_ALL = <?= json_encode($divs_js) ?>;
  const DIV_MAP  = <?= json_encode($div_map) ?>;
  const EXAMS    = <?= json_encode($exams_js) ?>;
  const GRADES   = <?= json_encode(array_values($grades)) ?>;

  let editing = null;   // row being edited, or null
  let viewing = false;  // read-only view mode
  let sheet   = null;   // { subjects, students, is_grade } currently shown
  let reqNo   = 0;      // latest sheet request (ignore older answers)
  let chain   = Promise.resolve();   // sheet requests run one after another (keeps the CSRF token valid)

  const esc = s => String(s == null ? '' : s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  const showError = m => { errorEl.textContent = m; errorEl.hidden = false; };
  const hint = t => { area.innerHTML = '<div class="hint">' + esc(t) + '</div>'; };
  const divsFor = c => (DIV_MAP[c] && DIV_MAP[c].length) ? DIV_MAP[c] : DIVS_ALL;

  function fill(el, list, ph, value, fallbackName) {
    const l = list.slice();
    if (value && !l.some(i => String(i.id) === String(value))) l.push({ id: String(value), name: fallbackName || value });
    el.innerHTML = '<option value="">' + esc(ph) + '</option>' +
      l.map(i => `<option value="${esc(i.id)}">${esc(i.name)}</option>`).join('');
    el.value = value ? String(value) : '';
  }

  /* ---------- list: search + serial numbers ---------- */
  function refresh() {
    const rowsEl = $('rows'); if (!rowsEl) return;
    const q = ($('search').value || '').trim().toLowerCase();
    const rows = Array.from(rowsEl.querySelectorAll('tr'));
    let n = 0;
    rows.forEach(tr => {
      const match = !q || tr.textContent.toLowerCase().includes(q);
      tr.hidden = !match;
      if (match) tr.querySelector('.num').textContent = ++n;
    });
    $('empty').hidden = n > 0;
    $('count').textContent = 'Showing ' + n + ' of ' + rows.length + ' mark lists';
  }
  if ($('search')) $('search').addEventListener('input', refresh);

  /* ---------- load sheet from server ---------- */
  function loadSheet() {
    sheet = null;
    errorEl.hidden = true;
    const cls = $('mClass').value, div = $('mDiv').value, exam = $('mExam').value;
    if (!cls || !div || !exam) { hint('Select class, division and exam to enter marks.'); return; }

    const my = ++reqNo;
    hint('Loading…');
    chain = chain.then(async () => {
      if (my !== reqNo) return;
      const fd = new FormData();
      fd.append('exam_id', exam); fd.append('class_id', cls); fd.append('division_id', div);
      fd.append(CSRF_NAME, CSRF_HASH);
      try {
        const res = await (await fetch(SHEET_URL, { method: 'POST', body: fd })).json();
        if (res.csrf) CSRF_HASH = res.csrf;
        if (my !== reqNo) return;
        if (!res.status) return hint(res.msg);
        if (!res.subjects.length) return hint('No mark allocation found for this class and exam. Add it in Mark Allocation first.');
        if (!res.students.length) return hint('No students found in this class and division.');
        if (res.final && !viewing) return hint('These marks are final submitted and cannot be edited.');
        if (res.exists && !editing && !viewing) return hint('Marks for this class, division and exam already exist. Edit them from the list.');
        buildSheet(res);
      } catch (err) {
        if (my === reqNo) hint('Could not load the sheet. Please try again.');
      }
    });
  }

  function buildSheet(res) {
    sheet = { subjects: res.subjects, students: res.students, is_grade: !!res.is_grade };
    const saved = res.marks || {};
    const g = sheet.is_grade;

    area.innerHTML = `
      <p class="keys" ${viewing ? 'hidden' : ''}>Use <kbd>&larr;</kbd> <kbd>&rarr;</kbd> <kbd>&uarr;</kbd> <kbd>&darr;</kbd> or <kbd>Enter</kbd> to move between cells.
        ${g ? 'This is a <b>grade</b> exam: enter a grade (' + esc(GRADES.join(', ')) + '). Numbers are not allowed.' : 'Marks cannot be more than the maximum shown for each subject.'}</p>
      <div class="sheet-wrap"><table class="sheet">
        <thead><tr>
          <th style="width:60px">SL</th><th style="width:130px">Admission No</th><th style="text-align:left">Student Name</th>
          ${sheet.subjects.map(s => `<th>${esc(s.name)}<small>${g ? '(Grade)' : '(Max: ' + esc(s.max) + ')'}</small></th>`).join('')}
        </tr></thead>
        <tbody>${sheet.students.map((st, r) => `
          <tr>
            <td>${r + 1}</td><td>${esc(st.adm_no || '-')}</td><td class="nm">${esc(st.name)}</td>
            ${sheet.subjects.map((s, c) => {
              const v = (saved[st.id] || {})[s.id];
              const val = v == null ? '' : esc(v);
              return `<td><input type="text" autocomplete="off" ${viewing ? 'readonly' : ''} ${g ? 'maxlength="2"' : 'inputmode="decimal" maxlength="7"'}
                       data-r="${r}" data-c="${c}" data-st="${st.id}" data-sub="${s.id}" data-max="${esc(s.max)}"
                       data-prev="${val}" value="${val}" aria-label="${esc(st.name)} ${esc(s.name)}"></td>`;
            }).join('')}
          </tr>`).join('')}
        </tbody></table></div>`;
  }

  /* ---------- typing rules ---------- */
  function blocked(inp, msg) {
    inp.value = inp.dataset.prev || '';
    inp.classList.add('bad');
    setTimeout(() => inp.classList.remove('bad'), 600);
    showError(msg);
  }

  area.addEventListener('input', e => {
    const inp = e.target;
    if (!inp.matches('.sheet input') || !sheet || viewing) return;
    errorEl.hidden = true;
    let v = inp.value;

    if (sheet.is_grade) {
      const cleaned = v.toUpperCase().replace(/[^A-E+]/g, '');
      if (cleaned !== v.toUpperCase()) { v = cleaned; inp.value = v; showError('This is a grade exam. Numbers are not allowed.'); }
      else { v = cleaned; inp.value = v; }
      if (v && !GRADES.some(gr => gr.startsWith(v))) return blocked(inp, 'Invalid grade. Allowed: ' + GRADES.join(', ') + '.');
    } else {
      v = v.replace(/[^0-9.]/g, '');
      const p = v.split('.');
      if (p.length > 2) v = p[0] + '.' + p.slice(1).join('');
      if (p[1] && p[1].length > 2) v = p[0] + '.' + p[1].slice(0, 2);
      inp.value = v;
      const max = Number(inp.dataset.max);
      if (v !== '' && v !== '.' && Number(v) > max) return blocked(inp, 'Marks cannot be more than ' + max + '.');
    }
    inp.dataset.prev = v;
  });

  /* arrow keys + Enter move between cells */
  const MOVES = { ArrowRight: [0, 1], ArrowLeft: [0, -1], ArrowDown: [1, 0], ArrowUp: [-1, 0], Enter: [1, 0] };
  area.addEventListener('keydown', e => {
    const inp = e.target;
    if (!inp.matches('.sheet input') || !MOVES[e.key]) return;
    e.preventDefault();
    const m = MOVES[e.key];
    const next = area.querySelector(`input[data-r="${+inp.dataset.r + m[0]}"][data-c="${+inp.dataset.c + m[1]}"]`);
    if (next) { next.focus(); next.select(); }
  });
  area.addEventListener('focusin', e => { if (e.target.matches('.sheet input')) e.target.select(); });

  /* ---------- modal ---------- */
  function open(m, view) {
    viewing = !!view;
    editing = (m && !view) ? m : null;
    form.reset();
    errorEl.hidden = true;
    $('addTitle').textContent = view ? 'View Marks' : (m ? 'Edit Marks' : 'Enter Marks');
    saveBtn.textContent = m ? 'Update Marks' : 'Save Marks';
    saveBtn.hidden = viewing;

    const cls  = m ? m.class_id    : (IS_ADMIN ? '' : SCOPE_CLASS);
    const div  = m ? m.division_id : (IS_ADMIN ? '' : SCOPE_DIV);
    const exam = m ? m.exam_id     : '';

    fill($('mClass'), CLASSES, 'Select Class', cls, m ? m.class_name : '');
    fill($('mDiv'), cls ? divsFor(cls) : [], cls ? 'Select Division' : 'Select Class first', div, m ? m.div_name : '');
    fill($('mExam'), EXAMS, 'Select Exam', exam, m ? m.exam_name : '');

    // class + division are read-only for non-admin; everything is locked while editing/viewing
    $('mClass').disabled = !!m || !IS_ADMIN;
    $('mDiv').disabled   = !!m || !IS_ADMIN || !cls;
    $('mExam').disabled  = !!m;

    modal.classList.add('open');
    modal.setAttribute('aria-hidden', 'false');
    loadSheet();
  }
  function close() {
    modal.classList.remove('open');
    modal.setAttribute('aria-hidden', 'true');
    reqNo++;
    sheet = null;
    editing = null;
    viewing = false;
    saveBtn.hidden = false;
  }

  $('mClass').addEventListener('change', () => {
    const c = $('mClass').value;
    fill($('mDiv'), c ? divsFor(c) : [], c ? 'Select Division' : 'Select Class first', '');
    $('mDiv').disabled = !c;
    loadSheet();
  });
  $('mDiv').addEventListener('change', loadSheet);
  $('mExam').addEventListener('change', loadSheet);

  const addBtn = $('openAddModal');
  if (addBtn) addBtn.addEventListener('click', () => open(null));
  modal.querySelectorAll('[data-close]').forEach(b => b.addEventListener('click', close));
  modal.addEventListener('click', e => { if (e.target === modal) close(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.classList.contains('open')) close(); });

  /* ---------- save ---------- */
  form.addEventListener('submit', async e => {
    e.preventDefault();
    if (viewing) return;
    errorEl.hidden = true;
    const cls = $('mClass').value, div = $('mDiv').value, exam = $('mExam').value;
    if (!cls)  return showError('Please select a class.');
    if (!div)  return showError('Please select a division.');
    if (!exam) return showError('Please select an exam.');
    if (!sheet) return showError('There are no students or subjects to enter marks for.');

    const marks = {};
    let filled = 0, problem = '';
    area.querySelectorAll('.sheet input').forEach(inp => {
      inp.classList.remove('bad');
      const v = inp.value.trim();
      if (v === '') return;

      let msg = '';
      if (sheet.is_grade) {
        if (!GRADES.includes(v.toUpperCase())) msg = 'Invalid grade "' + v + '". Allowed: ' + GRADES.join(', ') + '.';
      } else {
        const n = Number(v), max = Number(inp.dataset.max);
        if (!/^\d+(\.\d{1,2})?$/.test(v) || isNaN(n)) msg = 'Invalid marks "' + v + '".';
        else if (n > max) msg = 'Marks cannot be more than ' + max + '.';
      }
      if (msg) { inp.classList.add('bad'); if (!problem) problem = msg; return; }

      (marks[inp.dataset.st] = marks[inp.dataset.st] || {})[inp.dataset.sub] = sheet.is_grade ? v.toUpperCase() : v;
      filled++;
    });
    if (problem) return showError(problem);
    if (!filled) return showError('Please enter marks for at least one student.');

    const fd = new FormData();
    fd.append('exam_id', exam); fd.append('class_id', cls); fd.append('division_id', div);
    fd.append('is_edit', editing ? 1 : 0);
    fd.append('marks', JSON.stringify(marks));
    fd.append(CSRF_NAME, CSRF_HASH);

    saveBtn.disabled = true;
    try {
      const res = await (await fetch(SAVE_URL, { method: 'POST', body: fd })).json();
      if (res.csrf) CSRF_HASH = res.csrf;
      if (!res.status) return showError(res.msg);
      close();
      Swal.fire({ icon: 'success', title: 'Success', text: res.msg, timer: 1500, showConfirmButton: false })
          .then(() => location.reload());
    } catch (err) {
      showError('Something went wrong. Please try again.');
    } finally { saveBtn.disabled = false; }
  });

  /* ---------- list buttons: view / edit / final submit / delete ---------- */
  const rowsEl = $('rows');
  if (rowsEl) rowsEl.addEventListener('click', e => {
    const tr = e.target.closest('tr');
    if (!tr || !tr.dataset.m) return;
    const m = JSON.parse(tr.dataset.m);

    if (e.target.closest('[data-view]')) return open(m, true);
    if (e.target.closest('[data-edit]')) return open(m);

    if (e.target.closest('[data-final]')) {
      if (m.final) return;
      if (!m.complete) {
        return Swal.fire({
          icon: 'warning', title: 'Marks not complete',
          text: 'Please fill all marks and update first (' + m.filled + ' of ' + m.expected + ' filled).'
        });
      }
      return Swal.fire({
        title: 'Final submit?',
        text: m.exam_name + ' marks of ' + m.class_name + ' ' + m.div_name + ' will be locked. You cannot edit them after this.',
        icon: 'question', showCancelButton: true, confirmButtonColor: '#16a34a',
        confirmButtonText: 'Yes, final submit', cancelButtonText: 'Cancel'
      }).then(async r => {
        if (!r.isConfirmed) return;
        const fd = new FormData();
        fd.append('exam_id', m.exam_id); fd.append('class_id', m.class_id); fd.append('division_id', m.division_id);
        fd.append(CSRF_NAME, CSRF_HASH);
        try {
          const res = await (await fetch(FINAL_URL, { method: 'POST', body: fd })).json();
          if (res.csrf) CSRF_HASH = res.csrf;
          if (!res.status) return Swal.fire({ icon: 'error', title: 'Error', text: res.msg });
          Swal.fire({ icon: 'success', title: 'Submitted', text: res.msg, timer: 1500, showConfirmButton: false })
              .then(() => location.reload());
        } catch (err) {
          Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong.' });
        }
      });
    }

    if (e.target.closest('[data-del]')) {
      Swal.fire({
        title: 'Are you sure?',
        text: m.exam_name + ' marks of ' + m.class_name + ' ' + m.div_name + ' will be deleted.',
        icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc2626',
        confirmButtonText: 'Yes, delete it', cancelButtonText: 'Cancel'
      }).then(async r => {
        if (!r.isConfirmed) return;
        const fd = new FormData();
        fd.append('exam_id', m.exam_id); fd.append('class_id', m.class_id); fd.append('division_id', m.division_id);
        fd.append(CSRF_NAME, CSRF_HASH);
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

  refresh();
})();
</script>