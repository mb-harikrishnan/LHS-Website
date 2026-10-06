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
.preview{display:none;width:100%;max-height:180px;object-fit:contain;border-radius:8px}
.has-file .preview{display:block}
.field-error{color:#dc2626;font-size:13px;margin:12px 0 0}
.field-error[hidden]{display:none}
.field-note{font-size:12px;color:var(--slate-500);margin:-10px 0 16px}
</style>

<?php
$used = isset($used_types) ? $used_types : array();
$types_js = array();
foreach ($all_types as $t) { $types_js[] = $t->c_type; }
?>

<main class="page">
  <div class="page-header">
    <div>
      <h1 class="page-title">Co-Curricular List</h1>
      <p class="page-sub">Sports, arts and activity events with their photos</p>
    </div>
    <button class="btn btn-primary" type="button" id="openAddModal">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
      Add Co-Curricular
    </button>
  </div>

  <div class="card">
    <div class="table-toolbar">
      <div class="search-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="text" id="search" placeholder="Search by type or date…" aria-label="Search co-curricular list">
      </div>
      <select class="filter-select" id="typeFilter" aria-label="Filter by type">
        <option value="">All Types</option>
        <?php foreach ($used as $u): ?>
          <option value="<?= html_escape($u) ?>"><?= html_escape($u) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th>#</th><th>Date</th><th>Type</th><th>Image</th>
            <th style="width:120px;text-align:right">Actions</th>
          </tr>
        </thead>
        <tbody id="rows">
          <?php $n = 1; foreach ($rows as $r): ?>
          <tr data-type="<?= html_escape($r->c_type) ?>"
              data-date="<?= date('d-m-Y', strtotime($r->d_date)) ?>">
            <td class="num"><?= $n++ ?></td>
            <td><?= date('d-m-Y', strtotime($r->d_date)) ?></td>
            <td><span class="badge"><?= html_escape($r->c_type) ?></span></td>
            <td>
              <img class="thumb" src="http://localhost:8000/assets/images/gallery/<?= $r->c_images ?>" alt="<?= html_escape($r->c_type) ?> photo"></td>
            <td>
              <div class="row-actions">
                <button class="icon-btn" type="button" title="Edit" aria-label="Edit <?= html_escape($r->c_type) ?> entry"
                        data-edit
                        data-id="<?= (int)$r->n_slno ?>"
                        data-type="<?= html_escape($r->c_type) ?>"
                        data-date="<?= html_escape($r->d_date) ?>"
                        data-img="<?= 'http://localhost:8000/assets/images/gallery/' . $r->c_images ?>">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                </button>
                <button class="icon-btn danger" type="button" title="Delete" aria-label="Delete <?= html_escape($r->c_type) ?> entry"
                        data-del="<?= (int)$r->n_slno ?>">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14"/></svg>
                </button>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <div class="empty" id="empty" hidden>No co-curricular entries match. Add one with the button above.</div>
    </div>
    <div class="table-foot" id="count"></div>
  </div>
</main>

<!-- Add / Edit Modal -->
<div class="modal-overlay" id="addModal" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="addTitle">
    <div class="modal-head">
      <h2 id="addTitle">Add Co-Curricular</h2>
      <button class="modal-close" type="button" data-close aria-label="Close">&times;</button>
    </div>
    <form id="addForm" novalidate enctype="multipart/form-data">
      <input type="hidden" id="cId" value="0">
      <div class="modal-body">
        <label class="field-label" for="cType">Type <span class="req">*</span></label>
        <select id="cType" class="field-input"></select>
        <p class="field-note" id="typeNote" hidden>All types are already added.</p>

        <label class="field-label" for="cDate">Date <span class="req">*</span></label>
        <input type="date" id="cDate" class="field-input">

        <label class="field-label" for="cImage">Image <span class="req" id="imgReq">*</span></label>
        <label class="dropzone" id="dropzone" for="cImage">
          <img class="preview" id="preview" alt="Selected image preview">
          <span id="dropText"><strong>Click to choose</strong> or drag an image here</span>
          <small>JPG, PNG or WEBP, max 5 MB</small>
        </label>
        <input type="file" id="cImage" accept="image/png,image/jpeg,image/webp" hidden>

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
  const modal = $('addModal'), form = $('addForm'), fileInput = $('cImage');
  const dropzone = $('dropzone'), dropText = $('dropText'), preview = $('preview');
  const errorEl = $('formError'), typeSel = $('cType'), saveBtn = $('saveBtn');
  const MAX = 5 * 1024 * 1024;

  const ALL_TYPES  = <?= json_encode($types_js) ?>;   // from DB (master)
  const USED_TYPES = <?= json_encode(array_values($used)) ?>; // already inserted

  const SAVE_URL   = '<?= site_url('save_co_curricular') ?>';   // <-- change controller name
  const DELETE_URL = '<?= site_url('delete_co_curricular') ?>'; // <-- change controller name
  const CSRF_NAME  = '<?= $this->security->get_csrf_token_name() ?>';
  let   CSRF_HASH  = '<?= $this->security->get_csrf_hash() ?>';

  const esc = s => String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  const showError = m => { errorEl.textContent = m; errorEl.hidden = false; };

  /* ---------- list: search + filter ---------- */
  function applyFilter() {
    const q = $('search').value.trim().toLowerCase(), tf = $('typeFilter').value;
    const trs = [...document.querySelectorAll('#rows tr')];
    let shown = 0;
    trs.forEach(tr => {
      const ok = (!tf || tr.dataset.type === tf) &&
                 (!q || tr.dataset.type.toLowerCase().includes(q) || tr.dataset.date.includes(q));
      tr.hidden = !ok;
      if (ok) { shown++; tr.querySelector('.num').textContent = shown; }
    });
    $('empty').hidden = shown > 0;
    $('count').textContent = `Showing ${shown} of ${trs.length} entries`;
  }

  /* ---------- type select: hide types that already exist ---------- */
  function buildTypes(currentType) {
    // available = master types not yet used (+ current type while editing)
    const avail = ALL_TYPES.filter(t => !USED_TYPES.includes(t) || t === currentType);
    typeSel.innerHTML = '<option value="">Select type</option>' +
      avail.map(t => `<option value="${esc(t)}">${esc(t)}</option>`).join('');
    typeSel.value = currentType || '';
    $('typeNote').hidden = avail.length > 0;
  }

  /* ---------- modal ---------- */
  function resetForm() {
    form.reset(); $('cId').value = 0;
    dropzone.classList.remove('has-file'); preview.removeAttribute('src');
    dropText.innerHTML = '<strong>Click to choose</strong> or drag an image here';
    errorEl.hidden = true;
  }
  function open(edit) {
    resetForm();
    $('addTitle').textContent = edit ? 'Edit Co-Curricular' : 'Add Co-Curricular';
    $('imgReq').hidden = !!edit;
    buildTypes(edit ? edit.type : '');
    if (edit) {
      $('cId').value = edit.id;
      $('cDate').value = edit.date;
      preview.src = edit.img; dropzone.classList.add('has-file');
      dropText.textContent = 'Current image (choose a new one to replace)';
    }
    modal.classList.add('open'); modal.setAttribute('aria-hidden', 'false'); typeSel.focus();
  }
  const close = () => { modal.classList.remove('open'); modal.setAttribute('aria-hidden', 'true'); resetForm(); };

  function setFile(file) {
    errorEl.hidden = true;
    if (!/^image\/(png|jpe?g|webp)$/.test(file.type)) { fileInput.value = ''; return showError('Only JPG, PNG or WEBP images are allowed.'); }
    if (file.size > MAX) { fileInput.value = ''; return showError('Image must be 5 MB or smaller.'); }
    const r = new FileReader();
    r.onload = () => {
      preview.src = r.result; dropzone.classList.add('has-file');
      dropText.textContent = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
    };
    r.readAsDataURL(file);
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
    const id = +$('cId').value, type = typeSel.value, date = $('cDate').value, file = fileInput.files[0];
    if (!type) return showError('Please select a type.');
    if (!date) return showError('Please choose a date.');
    if (!id && !file) return showError('Please choose an image.');

    const fd = new FormData();
    fd.append('id', id); fd.append('type', type); fd.append('date', date);
    if (file) fd.append('image', file);
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
  $('rows').addEventListener('click', e => {
    const ed = e.target.closest('[data-edit]');
    if (ed) return open({ id: ed.dataset.id, type: ed.dataset.type, date: ed.dataset.date, img: ed.dataset.img });

    const del = e.target.closest('[data-del]');
    if (!del) return;
    Swal.fire({
      title: 'Are you sure?', text: 'This entry will be deleted.', icon: 'warning',
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
  $('typeFilter').addEventListener('change', applyFilter);
  applyFilter();
})();
</script>