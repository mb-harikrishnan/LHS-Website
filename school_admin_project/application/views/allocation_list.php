<?php
/* Receives: $details (grouped allocations), $classes (cmId, cmName), $exams (emId, emDisplayName),
             $subjects (smId, smName) */
$classes_js = array(); foreach ($classes as $c) { $classes_js[] = array('id' => (string)$c->cmId, 'name' => $c->cmName); }
$exams_js   = array(); foreach ($exams as $e)   { $exams_js[]   = array('id' => (string)$e->emId, 'name' => $e->emDisplayName); }
$subs_js    = array(); foreach ($subjects as $s){ $subs_js[]    = array('id' => (string)$s->smId, 'name' => $s->smName); }
?>
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
            <th style="width:160px">Exam</th>
            <th>Subject</th>
            <th style="width:80px;text-align:center">Edit</th>
            <th style="width:80px;text-align:center">Action</th>
          </tr>
        </thead>
        <tbody id="rows">
          <?php foreach ($details as $i => $d): ?>
          <tr data-a="<?= html_escape(json_encode($d)) ?>">
            <td class="num"><?= $i + 1 ?></td>
            <td><strong><?= html_escape($d['class_name']) ?></strong></td>
            <td><?= html_escape($d['exam_name']) ?></td>
            <td>
              <?php foreach ($d['subjects'] as $s): ?>
                <span class="tag"><?= html_escape($s['name']) ?><b><?= (int)$s['marks'] ?></b></span>
              <?php endforeach; ?>
            </td>
            <td style="text-align:center">
              <button class="icon-btn" type="button" title="Edit" data-edit aria-label="Edit">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
              </button>
            </td>
            <td style="text-align:center">
              <button class="icon-btn danger" type="button" title="Delete" data-del aria-label="Delete">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14"/></svg>
              </button>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <div class="empty" id="empty" <?= empty($details) ? '' : 'hidden' ?>>No mark allocations found. Add one with the button above.</div>
    </div>
    <div class="table-foot" id="count"></div>
  </div>
</main>

<!-- Add / Edit modal -->
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function () {
  const $ = id => document.getElementById(id);
  const modal = $('addModal'), form = $('addForm'), errorEl = $('formError'), saveBtn = $('saveBtn');

  const SAVE_URL   = '<?= site_url('save_allocation') ?>';
  const DELETE_URL = '<?= site_url('delete_allocation') ?>';
  const CSRF_NAME  = '<?= $this->security->get_csrf_token_name() ?>';
  let   CSRF_HASH  = '<?= $this->security->get_csrf_hash() ?>';

  const CLASSES  = <?= json_encode($classes_js) ?>;   // [{id, name}]
  const EXAMS    = <?= json_encode($exams_js) ?>;
  const SUBJECTS = <?= json_encode($subs_js) ?>;

  let oldExam = 0, oldClass = 0;   // set while editing
  let selected = [];               // chosen subject ids, in order
  let marks = {};                  // subject id -> marks typed so far

  const esc = s => String(s == null ? '' : s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  const subName = id => (SUBJECTS.find(s => s.id === String(id)) || { name: id }).name;
  const showError = m => { errorEl.textContent = m; errorEl.hidden = false; };

  function fill(el, list, ph, value, fallbackName) {
    let l = list.slice();
    if (value && !l.some(i => i.id === String(value))) l.push({ id: String(value), name: fallbackName || value });
    el.innerHTML = '<option value="">' + esc(ph) + '</option>' +
      l.map(i => `<option value="${esc(i.id)}">${esc(i.name)}</option>`).join('');
    el.value = value ? String(value) : '';
  }

  /* ---------- search + serial numbers ---------- */
  function refresh() {
    const q = $('search').value.trim().toLowerCase();
    const rows = Array.from($('rows').querySelectorAll('tr'));
    let n = 0;
    rows.forEach(tr => {
      const match = !q || tr.textContent.toLowerCase().includes(q);
      tr.hidden = !match;
      if (match) tr.querySelector('.num').textContent = ++n;
    });
    $('empty').hidden = n > 0;
    $('count').textContent = 'Showing ' + n + ' of ' + rows.length + ' allocations';
  }
  $('search').addEventListener('input', refresh);

  /* ---------- subject picker ---------- */
  function saveMarks() {
    $('marksList').querySelectorAll('input[data-sub]').forEach(inp => { marks[inp.dataset.sub] = inp.value; });
  }
  function renderPicker() {
    $('pickBox').innerHTML = selected.length
      ? selected.map(id => `<span class="chip"><button type="button" data-rm="${esc(id)}" aria-label="Remove">&times;</button>${esc(subName(id))}</span>`).join('')
      : '<span class="pick-ph">Click to select subjects</span>';
    const rest = SUBJECTS.filter(s => !selected.includes(s.id));
    $('pickMenu').innerHTML = rest.length
      ? rest.map(s => `<button type="button" data-add="${esc(s.id)}">${esc(s.name)}</button>`).join('')
      : '<div class="pick-empty">All subjects selected</div>';

    $('marksWrap').hidden = !selected.length;
    $('marksList').innerHTML = selected.map(id => `
      <div class="mark-row">
        <label for="mk-${esc(id)}">${esc(subName(id))}</label>
        <input class="field-input" type="number" min="1" step="1" id="mk-${esc(id)}" data-sub="${esc(id)}"
               placeholder="Enter max marks" value="${marks[id] != null ? esc(marks[id]) : ''}">
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
  function open(a) {
    form.reset();
    errorEl.hidden = true;
    oldExam  = a ? a.exam_id  : 0;
    oldClass = a ? a.class_id : 0;
    $('addTitle').textContent = a ? 'Edit Mark Allocation' : 'Add Mark Allocation';
    saveBtn.textContent = a ? 'Update' : 'Save';
    fill($('mClass'), CLASSES, 'Select Class', a ? a.class_id : '', a ? a.class_name : '');
    fill($('mExam'),  EXAMS,   'Select Exam',  a ? a.exam_id  : '', a ? a.exam_name  : '');
    selected = a ? a.subjects.map(s => String(s.id)) : [];
    marks = {};
    if (a) a.subjects.forEach(s => { marks[String(s.id)] = s.marks; });
    menuOpen(false);
    renderPicker();
    modal.classList.add('open');
    modal.setAttribute('aria-hidden', 'false');
  }
  function close() {
    modal.classList.remove('open');
    modal.setAttribute('aria-hidden', 'true');
    errorEl.hidden = true;
    oldExam = oldClass = 0;
  }
  $('openAddModal').addEventListener('click', () => open(null));
  modal.querySelectorAll('[data-close]').forEach(b => b.addEventListener('click', close));
  modal.addEventListener('click', e => { if (e.target === modal) close(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.classList.contains('open')) close(); });

  /* ---------- save ---------- */
  form.addEventListener('submit', async e => {
    e.preventDefault();
    saveMarks();
    const cls = $('mClass').value, exam = $('mExam').value;
    if (!cls)  return showError('Please select a class.');
    if (!exam) return showError('Please select an exam.');
    if (!selected.length) return showError('Please select at least one subject.');

    const subjects = [];
    for (const id of selected) {
      const m = Number(marks[id]);
      if (!marks[id] || !Number.isInteger(m) || m <= 0) return showError('Enter valid marks for ' + subName(id) + '.');
      subjects.push({ id: +id, marks: m });
    }

    const fd = new FormData();
    fd.append('exam_id', exam);
    fd.append('class_id', cls);
    fd.append('old_exam', oldExam);
    fd.append('old_class', oldClass);
    fd.append('subjects', JSON.stringify(subjects));
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

  /* ---------- edit / delete ---------- */
  $('rows').addEventListener('click', e => {
    const tr = e.target.closest('tr');
    if (!tr || !tr.dataset.a) return;
    const a = JSON.parse(tr.dataset.a);

    if (e.target.closest('[data-edit]')) return open(a);

    if (e.target.closest('[data-del]')) {
      Swal.fire({
        title: 'Are you sure?', text: a.class_name + ' - ' + a.exam_name + ' allocation will be deleted.', icon: 'warning',
        showCancelButton: true, confirmButtonColor: '#dc2626',
        confirmButtonText: 'Yes, delete it', cancelButtonText: 'Cancel'
      }).then(async r => {
        if (!r.isConfirmed) return;
        const fd = new FormData();
        fd.append('exam_id', a.exam_id);
        fd.append('class_id', a.class_id);
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