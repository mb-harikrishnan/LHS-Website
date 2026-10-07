<?php
/* Save as: application/views/members_area/questionpaper_list.php
   Receives from controller: $paper, $class_list (value => label), $from, $to */

$doc_base = 'http://localhost:8000/assets/documents/';
?>
<style>
/* modal */
.modal-overlay{position:fixed;inset:0;background:rgba(15,23,42,.5);display:none;align-items:center;justify-content:center;padding:16px;z-index:1000}
.modal-overlay.open{display:flex}
.modal{background:#fff;border-radius:12px;width:100%;max-width:480px;max-height:92vh;overflow:auto;box-shadow:0 20px 50px rgba(0,0,0,.25)}
.modal-head{display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid var(--slate-200)}
.modal-head h2{margin:0;font-size:18px}
.modal-close{background:none;border:0;font-size:26px;line-height:1;cursor:pointer;color:var(--slate-500)}
.modal-body{padding:20px}
.modal-foot{display:flex;justify-content:flex-end;gap:10px;padding:14px 20px;border-top:1px solid var(--slate-200)}
.field-label{display:block;font-size:13px;font-weight:600;margin:0 0 6px}
.req{color:#dc2626}
.field-input{width:100%;padding:9px 10px;border:1px solid var(--slate-300);border-radius:8px;font:inherit;margin-bottom:16px;background:#fff}
.dropzone{display:flex;flex-direction:column;align-items:center;gap:6px;text-align:center;padding:20px 12px;border:2px dashed var(--slate-300);border-radius:10px;cursor:pointer;color:var(--slate-500);transition:.15s}
.dropzone:hover,.dropzone.drag{border-color:var(--blue);background:#eff6ff}
.dropzone.has-file{border-style:solid;border-color:#16a34a;background:#f0fdf4;color:#166534}
.dropzone small{color:var(--slate-500)}
.field-error{color:#dc2626;font-size:13px;margin:12px 0 0}
.field-error[hidden]{display:none}

/* date filter */
.date-filter{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
.date-filter input[type=date]{padding:8px 10px;border:1px solid var(--slate-300);border-radius:8px;font:inherit;background:#fff}
.date-filter label{font-size:13px;color:var(--slate-500)}

/* action buttons */
.act-btn{display:inline-flex;align-items:center;justify-content:center;gap:6px;height:34px;padding:0 12px;border-radius:8px;font:inherit;font-size:13px;font-weight:600;line-height:1;cursor:pointer;text-decoration:none;white-space:nowrap;border:1px solid transparent;transition:background .15s,color .15s,border-color .15s,box-shadow .15s,transform .05s}
.act-btn svg{width:15px;height:15px;flex:none}
.act-btn:active{transform:translateY(1px)}
.act-btn:focus-visible{outline:2px solid #93c5fd;outline-offset:2px}
.act-btn.dl{background:#eff6ff;color:#1d4ed8;border-color:#bfdbfe}
.act-btn.dl:hover{background:#dbeafe;border-color:#93c5fd}
.act-btn.ed{background:#f8fafc;color:#334155;border-color:#cbd5e1;padding:0;width:34px}
.act-btn.ed:hover{background:#e2e8f0}
.act-btn.rm{background:#fef2f2;color:#b91c1c;border-color:#fecaca;padding:0;width:34px}
.act-btn.rm:hover{background:#dc2626;color:#fff;border-color:#dc2626;box-shadow:0 4px 10px rgba(220,38,38,.25)}
.cell-actions{display:flex;gap:6px;justify-content:flex-end}
</style>

<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Question Papers</h1>
      <p class="page-sub">Question papers by class with their documents</p>
    </div>
    <button class="btn btn-primary" type="button" id="openAddModal">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
      Add Question Paper
    </button>
  </div>

  <div class="card">
    <div class="table-toolbar">
      <div class="search-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="text" id="search" placeholder="Search by title or date…" aria-label="Search question papers">
      </div>

      <select class="filter-select" id="classFilter" aria-label="Filter by class">
        <option value="">All Classes</option>
        <?php foreach ($class_list as $val => $label): ?>
          <option value="<?= html_escape($val) ?>"><?= html_escape($label) ?></option>
        <?php endforeach; ?>
      </select>

      <form class="date-filter" method="get" action="<?= site_url('questionpaper_list') ?>">
        <label for="fFrom">From</label>
        <input type="date" id="fFrom" name="from" value="<?= html_escape($from) ?>">
        <label for="fTo">To</label>
        <input type="date" id="fTo" name="to" value="<?= html_escape($to) ?>">
        <button type="submit" class="btn">Filter</button>
        <?php if ($from !== '' || $to !== ''): ?>
          <a class="btn" href="<?= site_url('questionpaper_list') ?>">Clear</a>
        <?php endif; ?>
      </form>
    </div>

    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th>#SL</th><th>Date</th><th>Title</th><th>Class</th><th>PDF</th>
            <th style="width:130px;text-align:right">Action</th>
          </tr>
        </thead>
        <tbody id="rows">
          <?php foreach ($paper as $row):
            $label = isset($class_list[$row->c_class]) ? $class_list[$row->c_class] : $row->c_class;
            $dmy   = date('d-m-Y', strtotime($row->d_date));
            $ymd   = date('Y-m-d', strtotime($row->d_date));
          ?>
          <tr data-title="<?= html_escape($row->c_title) ?>"
              data-class="<?= html_escape($row->c_class) ?>"
              data-date="<?= $dmy ?>">
            <td class="num"></td>
            <td><?= $dmy ?></td>
            <td><?= html_escape($row->c_title) ?></td>
            <td><span class="badge"><?= html_escape($label) ?></span></td>
            <td>
              <?php if (!empty($row->c_document)): ?>
                <a class="act-btn dl" href="<?= html_escape($doc_base . rawurlencode($row->c_document)) ?>" target="_blank" rel="noopener">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8Z"/><path d="M14 3v5h5"/></svg>
                  View
                </a>
              <?php else: ?>
                No Document
              <?php endif; ?>
            </td>
            <td>
              <div class="cell-actions">
                <button class="act-btn ed" type="button" title="Edit" aria-label="Edit <?= html_escape($row->c_title) ?>"
                        data-edit
                        data-id="<?= (int)$row->n_slno ?>"
                        data-title="<?= html_escape($row->c_title) ?>"
                        data-class="<?= html_escape($row->c_class) ?>"
                        data-date="<?= $ymd ?>"
                        data-doc="<?= html_escape($row->c_document) ?>">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                </button>
                <button class="act-btn rm" type="button" title="Delete" aria-label="Delete <?= html_escape($row->c_title) ?>"
                        data-del="<?= (int)$row->n_slno ?>">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14M10 11v6M14 11v6"/></svg>
                </button>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <div class="empty" id="empty" hidden>No question papers found. Add one with the button above.</div>
    </div>
    <div class="table-foot" id="count"></div>
  </div>
</main>

<!-- Add / Edit Modal -->
<div class="modal-overlay" id="addModal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="addTitle">
    <div class="modal-head">
      <h2 id="addTitle">Add Question Paper</h2>
      <button class="modal-close" type="button" data-close aria-label="Close">&times;</button>
    </div>
    <form id="addForm" novalidate enctype="multipart/form-data">
      <input type="hidden" id="pId" value="0">
      <div class="modal-body">
        <label class="field-label" for="pTitle">Title <span class="req">*</span></label>
        <input type="text" id="pTitle" class="field-input" placeholder="e.g. Mathematics Mid Term 2026" maxlength="150">

        <label class="field-label" for="pClass">Class <span class="req">*</span></label>
        <select id="pClass" class="field-input">
          <option value="">Select class</option>
          <?php foreach ($class_list as $val => $label): ?>
            <option value="<?= html_escape($val) ?>"><?= html_escape($label) ?></option>
          <?php endforeach; ?>
        </select>

        <label class="field-label" for="pDate">Date <span class="req">*</span></label>
        <input type="date" id="pDate" class="field-input">

        <label class="field-label" for="pFile">Document / PDF <span class="req" id="fileReq">*</span></label>
        <label class="dropzone" id="dropzone" for="pFile">
          <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m17 8-5-5-5 5"/><path d="M12 3v12"/></svg>
          <span id="dropText"><strong>Click to choose</strong> or drag a file here</span>
          <small>PDF, DOC, DOCX, XLS, XLSX (max 10 MB)</small>
        </label>
        <input type="file" id="pFile" accept=".pdf,.doc,.docx,.xls,.xlsx" hidden>

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
  const modal = $('addModal'), form = $('addForm'), fileInput = $('pFile');
  const dropzone = $('dropzone'), dropText = $('dropText');
  const errorEl = $('formError'), saveBtn = $('saveBtn'), tbody = $('rows');
  const MAX = 10 * 1024 * 1024, ALLOWED = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];
  const DEFAULT_TEXT = '<strong>Click to choose</strong> or drag a file here';

  const SAVE_URL   = '<?= site_url('insert_paper') ?>';
  const DELETE_URL = '<?= site_url('delete_paper') ?>';
  const CSRF_NAME  = '<?= $this->security->get_csrf_token_name() ?>';
  let   CSRF_HASH  = '<?= $this->security->get_csrf_hash() ?>';

  const showError = m => { errorEl.textContent = m; errorEl.hidden = false; };
  const today = () => new Date().toISOString().slice(0, 10);

  /* ---------- list: search + class filter ---------- */
  function applyFilter() {
    const q = $('search').value.trim().toLowerCase(), cf = $('classFilter').value;
    const trs = [...tbody.querySelectorAll('tr')];
    let shown = 0;
    trs.forEach(tr => {
      const d = tr.dataset;
      const ok = (!cf || d.class === cf) &&
                 (!q || d.title.toLowerCase().includes(q) || d.date.includes(q));
      tr.hidden = !ok;
      if (ok) { shown++; tr.querySelector('.num').textContent = shown; }
    });
    $('empty').hidden = shown > 0;
    $('count').textContent = `Showing ${shown} of ${trs.length} question papers`;
  }

  /* ---------- add / edit modal ---------- */
  function resetForm() {
    form.reset(); $('pId').value = 0;
    dropzone.classList.remove('has-file');
    dropText.innerHTML = DEFAULT_TEXT;
    errorEl.hidden = true;
  }
  function open(edit) {
    resetForm();
    $('addTitle').textContent = edit ? 'Edit Question Paper' : 'Add Question Paper';
    $('fileReq').hidden = !!edit;
    $('pDate').value = edit ? edit.date : today();
    if (edit) {
      $('pId').value = edit.id;
      $('pTitle').value = edit.title;
      $('pClass').value = edit.class;
      if (edit.doc) {
        dropzone.classList.add('has-file');
        dropText.textContent = 'Current document kept (choose a new one to replace)';
      }
    }
    modal.classList.add('open'); modal.setAttribute('aria-hidden', 'false'); $('pTitle').focus();
  }
  const close = () => { modal.classList.remove('open'); modal.setAttribute('aria-hidden', 'true'); resetForm(); };

  function setFile(file) {
    errorEl.hidden = true;
    const ext = file.name.split('.').pop().toLowerCase();
    if (!ALLOWED.includes(ext)) { fileInput.value = ''; return showError('Only PDF, DOC, DOCX, XLS or XLSX files are allowed.'); }
    if (file.size > MAX)        { fileInput.value = ''; return showError('File must be 10 MB or smaller.'); }
    dropzone.classList.add('has-file');
    dropText.textContent = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
  }

  $('openAddModal').addEventListener('click', () => open(null));
  modal.querySelectorAll('[data-close]').forEach(b => b.addEventListener('click', close));
  modal.addEventListener('click', e => { if (e.target === modal) close(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.classList.contains('open')) close(); });

  fileInput.addEventListener('change', () => fileInput.files[0] && setFile(fileInput.files[0]));
  ['dragenter', 'dragover'].forEach(ev => dropzone.addEventListener(ev, e => { e.preventDefault(); dropzone.classList.add('drag'); }));
  ['dragleave', 'drop'].forEach(ev => dropzone.addEventListener(ev, e => { e.preventDefault(); dropzone.classList.remove('drag'); }));
  dropzone.addEventListener('drop', e => {
    const f = e.dataTransfer.files[0];
    if (f) { fileInput.files = e.dataTransfer.files; setFile(f); }
  });

  /* ---------- save (insert / update) ---------- */
  form.addEventListener('submit', async e => {
    e.preventDefault();
    const id = +$('pId').value, title = $('pTitle').value.trim(), cls = $('pClass').value,
          date = $('pDate').value, file = fileInput.files[0];
    if (!title) return showError('Please enter a title.');
    if (!cls)   return showError('Please select a class.');
    if (!date)  return showError('Please choose a date.');
    if (!id && !file) return showError('Please choose a document.');

    const fd = new FormData();
    fd.append('id', id); fd.append('title', title); fd.append('class', cls); fd.append('date', date);
    if (file) fd.append('file', file);
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

  /* ---------- edit + delete (row buttons) ---------- */
  tbody.addEventListener('click', e => {
    const ed = e.target.closest('[data-edit]');
    if (ed) return open({ id: ed.dataset.id, title: ed.dataset.title, class: ed.dataset.class, date: ed.dataset.date, doc: ed.dataset.doc });

    const del = e.target.closest('[data-del]');
    if (!del) return;
    Swal.fire({
      title: 'Are you sure?', text: 'This question paper will be deleted.', icon: 'warning',
      showCancelButton: true, confirmButtonColor: '#dc2626',
      confirmButtonText: 'Yes, delete it', cancelButtonText: 'Cancel'
    }).then(async r => {
      if (!r.isConfirmed) return;
      const fd = new FormData();
      fd.append('id', del.dataset.del); fd.append(CSRF_NAME, CSRF_HASH);
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
  });

  $('search').addEventListener('input', applyFilter);
  $('classFilter').addEventListener('change', applyFilter);
  applyFilter();
})();
</script>